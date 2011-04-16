//
//  CacheManager.h
//  bcm_ip
//
//  Created by User on 4/15/11.
//  Copyright 2011 __MyCompanyName__. All rights reserved.
//

#import <Foundation/Foundation.h>
#import "TBXML.h"
#import "Cache.h"

@interface CacheManager : NSObject {
	
	NSMutableArray* caches;	
	BOOL overwrite;
}

- (id) init;
- (void) fillInCachesOverwrite: (BOOL) _overwrite;
- (Cache*) getCacheByCommand: (NSString*) command;
- (NSArray*) getItInfraByAssetId: (NSString*) assetId;
- (NSArray*) getAssetsByProcessId: (NSString*) processId;
- (NSArray*) convertXmlCacheToArrayOfDictionaries: (TBXML*) tbXml;
@end
