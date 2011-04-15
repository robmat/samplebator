//
//  CacheManager.h
//  bcm_ip
//
//  Created by User on 4/15/11.
//  Copyright 2011 __MyCompanyName__. All rights reserved.
//

#import <Foundation/Foundation.h>


@interface CacheManager : NSObject {
	
	NSMutableArray* caches;	
}

- (id) init;
- (void) fillInCaches;

@end
