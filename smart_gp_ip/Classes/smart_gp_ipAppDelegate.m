//
//  smart_gp_ipAppDelegate.m
//  smart_gp_ip
//
//  Created by User on 5/9/11.
//  Copyright 2011 __MyCompanyName__. All rights reserved.
//

#import "smart_gp_ipAppDelegate.h"
#import "TableViewControllerWrapper.h"

@implementation smart_gp_ipAppDelegate

@synthesize window;


#pragma mark -
#pragma mark Application lifecycle

- (BOOL)application:(UIApplication *)application didFinishLaunchingWithOptions:(NSDictionary *)launchOptions {    
	navCtrl = [[UINavigationController alloc] init];
	window.rootViewController = navCtrl;
	TableViewControllerWrapper* tvcw = [[TableViewControllerWrapper alloc] initWithNibName:nil bundle:nil];
	[navCtrl pushViewController:tvcw animated:NO];
	[tvcw release];
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

#pragma mark -
#pragma mark Memory management

- (void)applicationDidReceiveMemoryWarning:(UIApplication *)application {
}
- (void)dealloc {
	[navCtrl release];
    [window release];
    [super dealloc];
}
@end
