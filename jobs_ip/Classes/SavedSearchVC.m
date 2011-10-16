#import "SavedSearchVC.h"
#import "ASIHTTPRequest.h"
#import "CXMLDocument.h"
#import "SearchResultsVC.h"
#import "SavedSearchTVC.h"
#import "ASIFormDataRequest.h"

@implementation SavedSearchVC

@synthesize cancelBtn, keywordSearchBar, locationSearchBar, tableView;

- (id)initWithNibName:(NSString *)nibNameOrNil bundle:(NSBundle *)nibBundleOrNil {
    self = [super initWithNibName:nibNameOrNil bundle:nibBundleOrNil];
    if (self) {
    }
    return self;
}
- (void)searchBarTextDidBeginEditing:(UISearchBar *)searchBar {
	cancelBtn.hidden = NO;
}
- (void)searchBarSearchButtonClicked:(UISearchBar *)searchBar {
	NSString* urlStr = @"http://jobstelecom.com/development/wsapi/mobile/advancedsearch";
	NSURL* url = [NSURL URLWithString:urlStr];
	ASIFormDataRequest* httpReq = [ASIFormDataRequest requestWithURL:url];
	httpReq.requestMethod = @"POST";
	[httpReq addPostValue:keywordSearchBar.text forKey:@"keywords"];
	[httpReq addPostValue:@"any" forKey:@"keywordsmode"];
	[httpReq addPostValue:locationSearchBar.text forKey:@"location"];
	[httpReq addPostValue:@"submit" forKey:@"submit"];
	httpReq.delegate = self;
	[httpReq startAsynchronous];
}
- (void)requestFinished:(ASIHTTPRequest *)request {
	if ([[[request url] lastPathComponent] isEqualToString:@"advancedsearch"]) {	
		NSLog(@"%@", [request responseString]);
		CXMLDocument* doc = [[CXMLDocument alloc] initWithData:[request responseData] options:0 error:nil];
		int resultCount = [[doc nodesForXPath:@"/AdvancedSearch/Job" error:nil] count];
		if (resultCount > 0) { 
			SearchResultsVC* srvc = [[SearchResultsVC alloc] init];
			srvc.doc = doc;
			srvc.keyword = keywordSearchBar.text;
			srvc.location = locationSearchBar.text;
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
		[doc release];
	}
	if ([[[request url] lastPathComponent] isEqualToString:@"listsavedsearches"]) {
		savedSearchesDoc = [[CXMLDocument alloc] initWithData:[request responseData] options:0 error:nil];
		if ([[savedSearchesDoc nodesForXPath:@"/SavedSearches/Search" error:nil] count] == 0) {
			tableView.hidden = YES;
		} else {
			[tableView reloadData];
		}
	}
}
- (void)requestFailed:(ASIHTTPRequest *)request {
	UIAlertView* alert = [[UIAlertView alloc] initWithTitle:@"Error" message:[[request error] localizedDescription] delegate:nil cancelButtonTitle:@"Ok" otherButtonTitles:nil];
	[alert show];
	[alert release];
}
- (UITableViewCell *)tableView:(UITableView *)tableView_ cellForRowAtIndexPath:(NSIndexPath *)indexPath {
	SavedSearchTVC *cell = nil;
    if (cell == nil) {
        NSArray *topLevelObjects = [[NSBundle mainBundle] loadNibNamed:@"SavedSearchTVC" owner:self options:nil];
        for (id currentObject in topLevelObjects){
            if ([currentObject isKindOfClass:[UITableViewCell class]]){
                cell =  (SavedSearchTVC *) currentObject;
                break;
            }
        }
    }
	cell.titleLbl.text = [[[savedSearchesDoc nodesForXPath:@"/SavedSearches/Search/Name" error:nil] objectAtIndex:indexPath.row] stringValue];
	cell.accessoryType = UITableViewCellAccessoryDetailDisclosureButton;
    return cell;
}
- (void)tableView:(UITableView *)tableView willDisplayCell:(UITableViewCell *)cell forRowAtIndexPath:(NSIndexPath *)indexPath {
	if (indexPath.row % 2 == 0) {
		cell.backgroundColor = [UIColor colorWithRed:0.447 green:0.454 blue:0.439 alpha:1];
	} else {
		cell.backgroundColor = [UIColor colorWithRed:0.341 green:0.345 blue:0.333 alpha:1];
	}

}
- (NSInteger)tableView:(UITableView *)tableView numberOfRowsInSection:(NSInteger)section {
	return [[savedSearchesDoc nodesForXPath:@"/SavedSearches/Search" error:nil] count];
}
- (void)tableView:(UITableView *)tableView didSelectRowAtIndexPath:(NSIndexPath *)indexPath {
	NSString* searchID = [[[savedSearchesDoc nodesForXPath:@"/SavedSearches/Search/SearchSID" error:nil] objectAtIndex:indexPath.row] stringValue];
	//TODO
	
}
- (NSInteger)numberOfSectionsInTableView:(UITableView *)tableView_ {
	return 1;
}
- (void)cancelAction: (id) sender {
	[keywordSearchBar resignFirstResponder];
	[locationSearchBar resignFirstResponder];
	cancelBtn.hidden = YES;
}
- (void)viewDidLoad {
    [super viewDidLoad];
	[self hideBackBtn];
	cancelBtn.hidden = YES;
	[[keywordSearchBar.subviews objectAtIndex:0] removeFromSuperview];
	[[locationSearchBar.subviews objectAtIndex:0] removeFromSuperview];
	tableView.backgroundColor = [UIColor clearColor];
	ASIHTTPRequest* request = [ASIHTTPRequest requestWithURL:[NSURL URLWithString:@"http://jobstelecom.com/development/wsapi/mobile/listsavedsearches"]];
	[request setRequestMethod:@"POST"];
	[request setDelegate:self];
	[request startAsynchronous];
}
- (void)dealloc {
    [super dealloc];
	[cancelBtn release];
	[keywordSearchBar release];
	[locationSearchBar release];
	[tableView release];
	[savedSearchesDoc release];
}


@end
