//
//  smart_gp_ipAppDelegate.m
//  smart_gp_ip
//
//  Created by User on 5/9/11.
//  Copyright 2011 __MyCompanyName__. All rights reserved.
//

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
    return YES;
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
