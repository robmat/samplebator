#import "SplashVC.h"
#import "MainMenuVC.h"
#import "rhead_sharepoint_ipAppDelegate.h"

@implementation SplashVC

- (void)viewDidLoad {
    [super viewDidLoad];
	[self hideBackBtn];
	[NSTimer scheduledTimerWithTimeInterval:3.0 target:self selector:@selector(timerAction) userInfo:nil repeats:NO];
	timerActionFlag = YES;
}
- (void)clickAction: (id) sender {
	timerActionFlag = NO;
	//MainMenuVC* mmvc = [[MainMenuVC alloc] init];
	//[self.navigationController pushViewController:mmvc animated:YES];
	//[mmvc release];
    [self laucnhTabController];
}
- (void)timerAction {
	if (timerActionFlag) {
		//MainMenuVC* mmvc = [[MainMenuVC alloc] init];
		//[self.navigationController pushViewController:mmvc animated:YES];
		//[mmvc release];
        [self laucnhTabController];
	}
}
- (void)laucnhTabController {
    [rhead_sharepoint_ipAppDelegate launchTabcontroller];
}
- (void) viewWillAppear:(BOOL)animated {
	self.navigationController.navigationBarHidden = YES;
}
- (void) viewWillDisappear:(BOOL)animated {

}
- (void)dealloc {	
    [super dealloc];
}

@end