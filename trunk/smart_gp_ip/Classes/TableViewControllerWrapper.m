
//Copyright Applicable Ltd 2011

#import "TableViewControllerWrapper.h"
#import "TableVieController.h"

@implementation TableViewControllerWrapper

@synthesize tableView, tableVC, dataArray;

- (void)viewDidLoad {
    [super viewDidLoad];
	tableVC = [[TableViewController alloc] initWithStyle:UITableViewStylePlain];
	tableVC.tableView = tableView;
	tableView.backgroundColor = [UIColor clearColor];
	[tableVC viewDidLoad];
	tableVC.dataArray = dataArray;
	tableVC.navController = self.navigationController;
}

- (void)didReceiveMemoryWarning {
    [super didReceiveMemoryWarning];
}

- (void)viewDidUnload {
    [super viewDidUnload];
}

- (void)dealloc {
	[tableVC release];
	[tableView release];
    [super dealloc];
}

@end
