#import "SplashVC.h"
#import "MainMenuVC.h"

@implementation SplashVC

- (void)viewDidLoad {
    [super viewDidLoad];
	[self hideBackBtn];
	[NSTimer scheduledTimerWithTimeInterval:1.5 target:self selector:@selector(timerAction) userInfo:nil repeats:NO];
}
- (void)timerAction {
	MainMenuVC* mmvc = [[MainMenuVC alloc] init];
	[self.navigationController pushViewController:mmvc animated:YES];
	[mmvc release];
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