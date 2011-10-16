#import "RefineVC.h"
#import "CXMLDocument.h"
#import "ActionSheetPicker.h"
#import "CXMLNode.h"
#import "ActionSheetPicker.h"
#import "ASIFormDataRequest.h"
#import "SearchResultsVC.h"

@implementation RefineVC

@synthesize keywordSearchBar, locationSearchBar, doc, tableView, pickerView, selectedFieldName, selectedFieldNode;

- (id)initWithNibName:(NSString *)nibNameOrNil bundle:(NSBundle *)nibBundleOrNil {
    self = [super initWithNibName:nibNameOrNil bundle:nibBundleOrNil];
    if (self) {
    }
    return self;
}
- (UITableViewCell *)tableView:(UITableView *)tableView_ cellForRowAtIndexPath:(NSIndexPath *)indexPath {
	static NSString *CellIdentifier = @"Cell";
    UITableViewCell *cell = [tableView dequeueReusableCellWithIdentifier:CellIdentifier];
    if (cell == nil) {
        cell = [[[UITableViewCell alloc] initWithStyle:UITableViewCellStyleValue1 reuseIdentifier:CellIdentifier] autorelease];
    }
    cell.textLabel.text = [[[doc nodesForXPath:@"/AdvancedSearch/RefineFields/Field/FieldName" error:nil] objectAtIndex:indexPath.row] stringValue];
	if ([refineParams objectForKey:cell.textLabel.text] == nil) {	
		cell.detailTextLabel.text = @"No selection";
	} else {
		cell.detailTextLabel.text = [refineParams objectForKey:cell.textLabel.text];
	}

	return cell;
}
- (void) tableView:(UITableView *)tableView_ didSelectRowAtIndexPath:(NSIndexPath *)indexPath {
	[[tableView_ cellForRowAtIndexPath:indexPath] setSelected:NO];
	self.selectedFieldName = [[[doc nodesForXPath:@"/AdvancedSearch/RefineFields/Field/FieldName" error:nil] objectAtIndex:indexPath.row] stringValue];
	NSArray* fieldNodes = [doc nodesForXPath:@"/AdvancedSearch/RefineFields/Field/Results" error:nil];
	NSString* prevSelVal = [refineParams objectForKey:selectedFieldName]; 
	self.selectedFieldNode = [fieldNodes objectAtIndex:indexPath.row];
	NSArray* nodes = [self.selectedFieldNode nodesForXPath:@"Result" error:nil];
	NSMutableArray* pickerData = [NSMutableArray arrayWithCapacity:[nodes count] + 1];
	[pickerData addObject:@"No selection"];
	int selectedIndex = 0;
	int i = 0;
	for (CXMLNode* node in nodes) {
		NSString* value = [[[node nodesForXPath:@"ResultValue" error:nil] objectAtIndex:0] stringValue];
		NSString* count = [[[node nodesForXPath:@"ResultCount" error:nil] objectAtIndex:0] stringValue];
		[pickerData addObject:[NSString stringWithFormat:@"%@ (%@)", value, count]];
		if ([prevSelVal isEqual:value]) {
			selectedIndex = i + 1;
		}
		i++;	 
	}
	[ActionSheetPicker displayActionPickerWithView:self.view data:pickerData 
									 selectedIndex:selectedIndex 
											target:self 
											action:@selector(pickerAction:) 
											 title:@"Select refine value"];
}
- (void) pickerAction: (id) element {
	int index = [element intValue];
	NSString* fieldValue = [[[self.selectedFieldNode nodesForXPath:@"Result/ResultValue" error:nil] objectAtIndex:--index < 0 ? 0 : index] stringValue];
	if (index == -1) {
		[refineParams removeObjectForKey:selectedFieldName];
	} else {
		[refineParams setObject:fieldValue forKey:selectedFieldName];
	}
	[tableView reloadData];

}
- (NSInteger)tableView:(UITableView *)tableView_ numberOfRowsInSection:(NSInteger)section {
	return [[doc nodesForXPath:@"/AdvancedSearch/RefineFields/Field" error:nil] count];
}
- (NSInteger)numberOfSectionsInTableView:(UITableView *)tableView {
	return 1;
}
- (IBAction)searchAction: (id) sender {
	ASIFormDataRequest* request = [ASIFormDataRequest requestWithURL:[NSURL URLWithString: @"http://jobstelecom.com/development/wsapi/mobile/advancedsearch"]];
	[request setRequestMethod:@"POST"];
	[request addPostValue:@"any" forKey:@"keywordsmode"];
	[request addPostValue:@"submit" forKey:@"submit"];
	[request addPostValue:keywordSearchBar.text forKey:@"keywords"];
	[request addPostValue:locationSearchBar.text forKey:@"location"];
	for (NSString* key in [refineParams keyEnumerator]) {
		NSString* val = [refineParams objectForKey:key];
		[request addPostValue:val forKey:key];
	}
	[request setDelegate:self];
	[request startAsynchronous];
}
- (void)requestFinished:(ASIHTTPRequest *)request {
	CXMLDocument* xmlDoc = [[CXMLDocument alloc] initWithData:[request responseData] options:0 error:nil];
	int resultCount = [[xmlDoc nodesForXPath:@"/AdvancedSearch/Job" error:nil] count];
	if (resultCount > 0) { 
		SearchResultsVC* srvc = [[SearchResultsVC alloc] init];
		srvc.doc = xmlDoc;
		srvc.location = keywordSearchBar.text;
		srvc.keyword = locationSearchBar.text;
		[self.navigationController pushViewController:srvc animated:YES];
		[srvc release];
	} else {
		UIAlertView* alert = [[UIAlertView alloc] initWithTitle:@"No search results." 
														message:[NSString stringWithFormat:@"Search for \"%@\" in \"%@\" \n No Results", 
																 keywordSearchBar.text == nil ? @"" : keywordSearchBar.text,
																 locationSearchBar.text  == nil ? @"" : locationSearchBar.text] 
													   delegate:nil cancelButtonTitle:@"Ok" 
											  otherButtonTitles:nil];
		[alert show];
		[alert release];
	}
	[xmlDoc release];
}

- (void)requestFailed:(ASIHTTPRequest *)request {
	UIAlertView* alert = [[UIAlertView alloc] initWithTitle:@"Error" message:[[request error] localizedDescription] delegate:nil cancelButtonTitle:@"Ok" otherButtonTitles:nil];
	[alert show];
	[alert release];
}
- (void)viewDidLoad {
    [super viewDidLoad];
	[[keywordSearchBar.subviews objectAtIndex:0] removeFromSuperview];
	[[locationSearchBar.subviews objectAtIndex:0] removeFromSuperview];
	backBtn.hidden = YES;
	[tableView setBackgroundColor:[UIColor clearColor]];
	refineParams = [[NSMutableDictionary alloc] init];
}
- (void)dealloc {
    [super dealloc];
    [locationSearchBar release];
	[keywordSearchBar release];
	[doc release];
	[tableView release];
	[pickerView release];
	[refineParams release];
	[selectedFieldName release];
	[selectedFieldNode release];
}

@end
