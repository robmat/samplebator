//
//  Cache.h
//  bcm_ip
//
//  Created by User on 4/15/11.
//  Copyright 2011 __MyCompanyName__. All rights reserved.
//

#import <Foundation/Foundation.h>
#import "TBXML.h"

@interface Cache : NSObject {

	NSString* command;
	BOOL filled;
	TBXML* xmlDoc;
}

@property (nonatomic, retain) NSString* command;
@property (nonatomic, retain) TBXML* xmlDoc;

- (id) initWithCommand: (NSString*) _command;
- (void) fillInCacheOverwrite: (BOOL) overwrite;
- (NSString*) getFilePath;

@end
