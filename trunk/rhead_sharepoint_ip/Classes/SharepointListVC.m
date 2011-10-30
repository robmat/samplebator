#import "SharepointListVC.h"
#import "SharepointListTVC.h"

@implementation SharepointListVC

@synthesize tableView, sltvc, listsData, datesData;

- (void)viewDidLoad {
    [super viewDidLoad];
	sltvc = [[SharepointListTVC alloc] initWithStyle:UITableViewStylePlain];
	sltvc.listsData = listsData;
	sltvc.tableView = tableView;
	sltvc.navCntrl = self.navigationController;
	[sltvc viewDidLoad];
}

- (void)dealloc {
    [super dealloc];
	[tableView release];
	[sltvc release];
	[listsData release];
	[datesData release];
}

@end
