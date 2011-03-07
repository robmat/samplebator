//
//  bcm_ipAppDelegate.h
//  bcm_ip
//
//  Created by User on 3/4/11.
//  Copyright 2011 __MyCompanyName__. All rights reserved.
//

#import <UIKit/UIKit.h>
#import "LoginViewController.h"

@interface bcm_ipAppDelegate : NSObject <UIApplicationDelegate> {
    UIWindow *window;
	LoginViewController* loginController;
}

@property (nonatomic, retain) IBOutlet UIWindow *window;
@property (nonatomic, retain) IBOutlet LoginViewController* loginController;

+ (NSString*) baseURL;
+ (NSString*) apiSuffix;
+ (NSString*) getLoginDataFilePath;
+ (NSString*) getDictFilePath;
+ (NSString*) getFullURLWithSite;

@end

