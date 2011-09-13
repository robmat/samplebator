#import "SavedSearchVC.h"
#import "ASIHTTPRequest.h"

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
	NSString* urlStr = @"http://jobstelecom.com/development/wsapi/mobile/runsimplesearch?keywords=insurance&keywordsmode=any&location=&submit=submit";
	NSURL* url = [NSURL URLWithString:urlStr];
	ASIHTTPRequest* httpReq = [ASIHTTPRequest requestWithURL:url];
	httpReq.requestMethod = @"GET";
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
