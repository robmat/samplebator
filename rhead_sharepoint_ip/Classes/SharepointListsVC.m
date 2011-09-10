#import "SharepointListsVC.h"
#import "SharepointListsTVC.h"

@implementation SharepointListsVC

@synthesize tableView, tableVC, listsData;

- (void)viewDidLoad {
    [super viewDidLoad];
	tableVC = [[SharepointListsTVC alloc] initWithStyle:UITableViewStylePlain];
	tableVC.listsData = listsData;
	tableVC.tableView = tableView;
	tableVC.navCntrl = self.navigationController;
	[tableVC viewDidLoad];
}

- (void)dealloc {
    [super dealloc];
	[tableView release];
	[tableVC release];
	[listsData release];
}

@end
