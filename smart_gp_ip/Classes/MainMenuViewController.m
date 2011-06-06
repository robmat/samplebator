#import "MainMenuViewController.h"
#import "TableViewControllerWrapper.h"
#import "LogScreenViewController.h"
#import "RemindersMainMenuViewController.h"
#import "WebViewController.h"

@implementation MainMenuViewController

- (IBAction) remindersScreenAction: (id) sender {
	RemindersMainMenuViewController* rmmvc = [[RemindersMainMenuViewController alloc] initWithNibName:nil bundle:nil];
	[self.navigationController pushViewController:rmmvc animated:YES];
	[rmmvc release];
}
- (IBAction) logScreenAction: (id) sender {
	LogScreenViewController* lsvc = [[LogScreenViewController alloc] initWithNibName:nil bundle:nil];
	[self.navigationController pushViewController:lsvc animated:YES];
	[lsvc release];
}
- (IBAction) localHealthServAction: (id) sender {
	TableViewControllerWrapper* tvcw = [[TableViewControllerWrapper alloc] initWithNibName:nil bundle:nil];
	tvcw.dataArray = [[NSDictionary dictionaryWithContentsOfFile:[[NSBundle mainBundle] pathForResource:@"smart_gp_data" ofType:@"plist"]] objectForKey:@"Children"];
	tvcw.title = @"Smart GP";
	[self.navigationController pushViewController:tvcw animated:YES];
	[tvcw release];
}
- (IBAction) newsAction: (id) sender {
	WebViewController* wvc = [[WebViewController alloc] initWithNibName:nil bundle:nil];
	wvc.url = @"http://www.healtheastcic.co.uk/News.aspx";
	[self.navigationController pushViewController:wvc animated:YES];
	[wvc release];
}
- (IBAction) contactAction: (id) sender {

}
- (void)viewDidLoad {
    [super viewDidLoad];
	self.navigationItem.hidesBackButton = YES;
    self.title = @"Smart GP";
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
