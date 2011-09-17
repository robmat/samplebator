#import "SearchResultsVC.h"
#import "SearchResultsTVC.h"

@implementation SearchResultsVC

@synthesize doc, tableVew;

- (id)initWithNibName:(NSString *)nibNameOrNil bundle:(NSBundle *)nibBundleOrNil {
    self = [super initWithNibName:nibNameOrNil bundle:nibBundleOrNil];
    if (self) {

    }
    return self;
}

- (void)saveSearchAction: (id) sender {
	
}

- (void)refineAction: (id) sender {

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
}


@end
