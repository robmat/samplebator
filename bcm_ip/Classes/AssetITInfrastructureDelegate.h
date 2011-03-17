//
//  AssetITInfrastructure.h
//  bcm_ip
//
//  Created by User on 3/17/11.
//  Copyright 2011 __MyCompanyName__. All rights reserved.
//

#import <Foundation/Foundation.h>
#import "ClickListener.h"

@interface AssetITInfrastructureDelegate: NSObject <ClickListener> {

	UINavigationController* navigationController;
	
}

@property (nonatomic, retain) UINavigationController* navigationController;

@end
