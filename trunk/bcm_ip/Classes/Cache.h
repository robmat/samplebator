//
//  Cache.h
//  bcm_ip
//
//  Created by User on 4/15/11.
//  Copyright 2011 __MyCompanyName__. All rights reserved.
//

#import <Foundation/Foundation.h>


@interface Cache : NSObject {

	NSString* command;
	BOOL filled;
}

@property (nonatomic, retain) NSString* command;

- (id) initWithCommand: (NSString*) _command;
- (void) fillInCache;

@end
