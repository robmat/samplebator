#import "TeasecVC.h"
#import "MainMenuVC.h"

@implementation TeasecVC

- (void)viewDidLoad {
	shouldIPlayPlak = NO;
	[super viewDidLoad];
	[NSTimer scheduledTimerWithTimeInterval:1.5 target:self selector:@selector(timerAction) userInfo:nil repeats:NO];
}

- (void)timerAction {
	
}	

- (void)dealloc {
    [super dealloc];
}

@end
