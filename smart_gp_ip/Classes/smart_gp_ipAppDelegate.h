//
//  smart_gp_ipAppDelegate.h
//  smart_gp_ip
//
//  Created by User on 5/9/11.
//  Copyright 2011 __MyCompanyName__. All rights reserved.
//

#import <UIKit/UIKit.h>

@interface smart_gp_ipAppDelegate : NSObject <UIApplicationDelegate> {
    UIWindow *window;
	UINavigationController* navCtrl;
}

@property (nonatomic, retain) IBOutlet UIWindow *window;

- (void) handleNotification: (UILocalNotification*) ln;

@end

