#import "SplashScreenVC.h"
#import "TeasecVC.h"
#import "MainMenuVC.h"

@implementation SplashScreenVC

- (void)viewDidLoad {
	shouldIPlayPlak = NO;
    [super viewDidLoad];
	[NSTimer scheduledTimerWithTimeInterval:1.5 target:self selector:@selector(timerAction) userInfo:nil repeats:NO];
	backBtn.hidden = YES;
}

- (void)timerAction {
	MainMenuVC* mmvc = [[MainMenuVC alloc] init];
	[self.navigationController pushViewController:mmvc animated:YES];
	[mmvc release];
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
