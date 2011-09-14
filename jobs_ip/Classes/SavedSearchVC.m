#import "SavedSearchVC.h"
#import "ASIHTTPRequest.h"
#import "CXMLDocument.h"
#import "SearchResultsVC.h"

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
	NSString* urlStr = @"http://jobstelecom.com/development/wsapi/mobile/runsimplesearch?keywords=%@&keywordsmode=any&location=%@&submit=submit";
	urlStr = [NSString stringWithFormat:urlStr, keywordSearchBar.text, locationSearchBar.text];
	NSURL* url = [NSURL URLWithString:urlStr];
	ASIHTTPRequest* httpReq = [ASIHTTPRequest requestWithURL:url];
	httpReq.requestMethod = @"GET";
	httpReq.delegate = self;
	[httpReq startAsynchronous];
}
- (void)requestFinished:(ASIHTTPRequest *)request {
	NSLog(@"%@", [request responseString]);
	CXMLDocument* doc = [[CXMLDocument alloc] initWithData:[request responseData] options:0 error:nil];
	SearchResultsVC* srvc = [[SearchResultsVC alloc] init];
	srvc.doc = doc;
	[doc release];
	[self.navigationController pushViewController:srvc animated:YES];
	[srvc release];
}
- (void)requestFailed:(ASIHTTPRequest *)request {
	UIAlertView* alert = [[UIAlertView alloc] initWithTitle:@"Error" message:[[request error] localizedDescription] delegate:nil cancelButtonTitle:@"Ok" otherButtonTitles:nil];
	[alert show];
	[alert release];
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
}
- (void)dealloc {
    [super dealloc];
	[cancelBtn release];
	[keywordSearchBar release];
	[locationSearchBar release];
	[tableView release];
}


@end
