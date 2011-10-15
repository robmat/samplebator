#import "SearchResultsVC.h"
#import "SearchResultsTVC.h"
#import "RefineVC.h"

@implementation SearchResultsVC

@synthesize doc, tableVew, location, keyword;

- (id)initWithNibName:(NSString *)nibNameOrNil bundle:(NSBundle *)nibBundleOrNil {
    self = [super initWithNibName:nibNameOrNil bundle:nibBundleOrNil];
    if (self) {

    }
    return self;
}

- (void)saveSearchAction: (id) sender {
	
}

- (void)refineAction: (id) sender {
	RefineVC* rvc = [[RefineVC alloc] init];
	[self.navigationController pushViewController:rvc animated:YES];
	rvc.locationSearchBar.text = location;
	rvc.keywordSearchBar.text = keyword;
	rvc.doc = doc;
	[rvc release];
}

- (void)viewDidLoad {
    [super viewDidLoad];
	tableViewController = [[SearchResultsTVC alloc] initWithStyle:UITableViewStylePlain];
	tableViewController.tableView = tableVew;
	tableViewController.doc = doc;
	tableViewController.navCntrl = self.navigationController;
	[tableViewController viewDidLoad];
}

- (void)dealloc {
    [super dealloc];
	[doc release];
	[tableVew release];
	[keyword release];
	[location release];
}


@end
