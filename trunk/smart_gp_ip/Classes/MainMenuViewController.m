#import "MainMenuViewController.h"
#import "TableViewControllerWrapper.h"

@implementation MainMenuViewController

- (IBAction) localHealthServAction: (id) sender {
	TableViewControllerWrapper* tvcw = [[TableViewControllerWrapper alloc] initWithNibName:nil bundle:nil];
	tvcw.dataArray = [[NSDictionary dictionaryWithContentsOfFile:[[NSBundle mainBundle] pathForResource:@"smart_gp_data" ofType:@"plist"]] objectForKey:@"Children"];
	tvcw.title = @"Data View";
	[self.navigationController pushViewController:tvcw animated:YES];
	[tvcw release];
}
- (void)viewDidLoad {
    [super viewDidLoad];
	self.navigationItem.hidesBackButton = YES;
    self.title = @"Main menu";
}
- (void)didReceiveMemoryWarning {
    [super didReceiveMemoryWarning];
}
- (void)viewDidUnload {
    [super viewDidUnload];
}
- (void)dealloc {
    [super dealloc];
}

@end
