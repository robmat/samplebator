#import "SplashScreenVC.h"
#import "TeasecVC.h"

@implementation SplashScreenVC

- (void)viewDidLoad {
	shouldIPlayPlak = NO;
    [super viewDidLoad];
	[NSTimer scheduledTimerWithTimeInterval:1.5 target:self selector:@selector(timerAction) userInfo:nil repeats:NO];
}

- (void)timerAction {
	TeasecVC* teaserVC = [[TeasecVC alloc] init];
	[self.navigationController pushViewController:teaserVC animated:YES];
	[teaserVC release];
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
