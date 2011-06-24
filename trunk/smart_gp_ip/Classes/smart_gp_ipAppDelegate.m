
//Copyright Applicable Ltd 2011

#import "smart_gp_ipAppDelegate.h"
#import "TableViewControllerWrapper.h"
#import "SplashScreenViewController.h"

@implementation smart_gp_ipAppDelegate

@synthesize window;

- (BOOL)application:(UIApplication *)application didFinishLaunchingWithOptions:(NSDictionary *)launchOptions {    
	navCtrl = [[UINavigationController alloc] init];
	window.rootViewController = navCtrl;
	SplashScreenViewController* svc = [[SplashScreenViewController alloc] initWithNibName:nil bundle:nil];
	[navCtrl pushViewController:svc animated:NO];
	[svc release];
    [window makeKeyAndVisible];
	UILocalNotification* ln = [launchOptions objectForKey:UIApplicationLaunchOptionsLocalNotificationKey];
	if (ln) {
		[self handleNotification:ln];
	}
	return YES;
}
- (void) handleNotification: (UILocalNotification*) ln {
	UIAlertView* alert = [[UIAlertView alloc] initWithTitle:@"Reminder" message:ln.alertBody delegate:nil cancelButtonTitle:@"Ok" otherButtonTitles:nil];
	[alert show];
	[alert release];
}
- (void)application:(UIApplication *)application didReceiveLocalNotification:(UILocalNotification *)notification {
	if (notification) {
		[self handleNotification:notification];
	}
}
- (void)applicationWillResignActive:(UIApplication *)application {
}
- (void)applicationDidEnterBackground:(UIApplication *)application {
}
- (void)applicationWillEnterForeground:(UIApplication *)application {
}
- (void)applicationDidBecomeActive:(UIApplication *)application {
}
- (void)applicationWillTerminate:(UIApplication *)application {
}
- (void)applicationDidReceiveMemoryWarning:(UIApplication *)application {
}
- (void)dealloc {
	[navCtrl release];
    [window release];
    [super dealloc];
}
@end
