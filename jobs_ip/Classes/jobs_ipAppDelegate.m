#import "jobs_ipAppDelegate.h"
#import "SplashVC.h"
#import "ASIHTTPRequest.h"

@implementation jobs_ipAppDelegate

@synthesize window, navigationController;

- (BOOL)application:(UIApplication *)application didFinishLaunchingWithOptions:(NSDictionary *)launchOptions {    
    self.navigationController = [[UINavigationController alloc] init];
	[window addSubview:navigationController.view];
	SplashVC* svc = [[SplashVC alloc] initWithNibName:nil bundle:nil];
	[navigationController pushViewController:svc animated:YES];
	[svc release];
	[window makeKeyAndVisible];	
    return YES;
}

- (void)applicationWillResignActive:(UIApplication *)application {}

- (void)applicationDidEnterBackground:(UIApplication *)application {}

- (void)applicationWillEnterForeground:(UIApplication *)application {}

- (void)applicationDidBecomeActive:(UIApplication *)application {}

- (void)applicationWillTerminate:(UIApplication *)application {}

- (void)applicationDidReceiveMemoryWarning:(UIApplication *)application {}

- (void)dealloc {
	[navigationController release];
    [window release];
    [super dealloc];
}

@end

@implementation UINavigationBar (CustomImage)
- (void)drawRect:(CGRect)rect {
	UIImage *image = [UIImage imageNamed: @"nav_bar_background.png"];
	[image drawInRect:CGRectMake(0, 0, self.frame.size.width, self.frame.size.height)];
}
@end

