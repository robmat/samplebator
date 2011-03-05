//
//  bcm_ipAppDelegate.h
//  bcm_ip
//
//  Created by User on 3/4/11.
//  Copyright 2011 __MyCompanyName__. All rights reserved.
//

#import <UIKit/UIKit.h>
#import "LoginFormController.h"

@interface bcm_ipAppDelegate : NSObject <UIApplicationDelegate> {
    UIWindow *window;
	LoginFormController* loginController;
}

@property (nonatomic, retain) IBOutlet UIWindow *window;
@property (nonatomic, retain) IBOutlet LoginFormController* loginController;

@end

