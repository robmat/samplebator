//
//  NotifyTemplatesDelegate.h
//  bcm_ip
//
//  Created by User on 3/22/11.
//  Copyright 2011 __MyCompanyName__. All rights reserved.
//

#import <Foundation/Foundation.h>
#import "ClickListener.h"

@interface NotifyTemplatesDelegate : NSObject <ClickListener> {

	UINavigationController* navigationController;
}

@property (nonatomic, retain) UINavigationController* navigationController;

@end
