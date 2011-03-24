//
//  rac_ipAppDelegate.h
//  rac_ip
//
//  Created by User on 3/24/11.
//  Copyright 2011 __MyCompanyName__. All rights reserved.
//

#import <UIKit/UIKit.h>

@interface rac_ipAppDelegate : NSObject <UIApplicationDelegate> {
    UIWindow *window;
	UINavigationController* navController;
}

@property (nonatomic, retain) IBOutlet UIWindow *window;

@end

