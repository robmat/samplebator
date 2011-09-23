#import "SplashScreenVC.h"
#import "TeasecVC.h"
#import "MainMenuVC.h"

@implementation SplashScreenVC

- (void)viewDidLoad {
	NSArray* paths = NSSearchPathForDirectoriesInDomains(NSDocumentDirectory, NSUserDomainMask, YES); 
	NSString* documentsDirectoryPath = [paths objectAtIndex:0];
	NSArray* skipArr = [NSArray arrayWithContentsOfFile:[NSString stringWithFormat:@"%@/%@", documentsDirectoryPath, @"skipArr.plist"]];
	shouldIPlayPlak = NO;
    [super viewDidLoad];
	int interval = [skipArr count] > 0 ? [[skipArr objectAtIndex:0] intValue] : 10;
	timer = [NSTimer scheduledTimerWithTimeInterval:interval target:self selector:@selector(timerAction) userInfo:nil repeats:NO];
	backBtn.hidden = YES;
	skipArr = [NSArray arrayWithObject:[NSNumber numberWithInt:3]];
	[skipArr writeToFile:[NSString stringWithFormat:@"%@/%@", documentsDirectoryPath, @"skipArr.plist"] atomically:NO];
}

- (IBAction)skipAction {
	[self timerAction];
	[timer invalidate];
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
	[timer release];
}


@end
