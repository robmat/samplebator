#import "RefineVC.h"
#import "CXMLDocument.h"
#import "ActionSheetPicker.h"
#import "CXMLNode.h"
#import "ActionSheetPicker.h"

@implementation RefineVC

@synthesize keywordSearchBar, locationSearchBar, doc, tableView, pickerView;

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
    cell.textLabel.text = [[[doc nodesForXPath:@"/RunSimpleSearch/RefineFields/Field/FieldName" error:nil] objectAtIndex:indexPath.row] stringValue];
    return cell;
}
- (void) tableView:(UITableView *)tableView_ didSelectRowAtIndexPath:(NSIndexPath *)indexPath {
	[[tableView_ cellForRowAtIndexPath:indexPath] setSelected:NO];
	NSArray* fieldNodes = [doc nodesForXPath:@"/RunSimpleSearch/RefineFields/Field/Results" error:nil];
	CXMLNode* fieldNode = [fieldNodes objectAtIndex:indexPath.row];
	NSArray* nodes = [fieldNode nodesForXPath:@"Result" error:nil];
	NSMutableArray* pickerData = [NSMutableArray arrayWithCapacity:[nodes count]];
	for (CXMLNode* node in nodes) {
		NSString* value = [[[node nodesForXPath:@"ResultValue" error:nil] objectAtIndex:0] stringValue];
		NSString* count = [[[node nodesForXPath:@"ResultCount" error:nil] objectAtIndex:0] stringValue];
		[pickerData addObject:[NSString stringWithFormat:@"%@ (%@)", value, count]];
	}
	[ActionSheetPicker displayActionPickerWithView:self.view data:pickerData 
									 selectedIndex:selectedIndex 
											target:self 
											action:@selector(pickerAction:) 
											 title:@"Select refine value"];
}
- (void) pickerAction: (id) element {
	NSLog(@"%d %@",selectedIndex,element);
}
- (NSInteger)tableView:(UITableView *)tableView_ numberOfRowsInSection:(NSInteger)section {
	return [[doc nodesForXPath:@"/RunSimpleSearch/RefineFields/Field" error:nil] count];
}
- (NSInteger)numberOfSectionsInTableView:(UITableView *)tableView {
	return 1;
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
}

@end
