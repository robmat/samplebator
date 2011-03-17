//
//  ClickListener.h
//  bcm_ip
//
//  Created by User on 3/17/11.
//  Copyright 2011 __MyCompanyName__. All rights reserved.
//

#import <UIKit/UIKit.h>


@protocol ClickListener <NSObject>

- (void) detailClicked: (NSString*) idStr;

@end
