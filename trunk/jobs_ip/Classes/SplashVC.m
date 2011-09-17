#import "SplashVC.h"
#import "SavedSearchVC.h"

@implementation SplashVC

- (id)initWithNibName:(NSString *)nibNameOrNil bundle:(NSBundle *)nibBundleOrNil {
    self = [super initWithNibName:nibNameOrNil bundle:nibBundleOrNil];
    if (self) {
    }
    return self;
}

- (void)viewDidLoad {
    [super viewDidLoad];
	[self hideBackBtn];
	[NSTimer scheduledTimerWithTimeInterval:1.5 target:self selector:@selector(timerAction) userInfo:nil repeats:NO];
}
- (void)timerAction {
	SavedSearchVC* mmvc = [[SavedSearchVC alloc] init];
	[self.navigationController pushViewController:mmvc animated:YES];
	[mmvc release];
}
- (void)dealloc {
    [super dealloc];
}


@end
