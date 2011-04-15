//
//  CacheManager.m
//  bcm_ip
//
//  Created by User on 4/15/11.
//  Copyright 2011 __MyCompanyName__. All rights reserved.
//

#import "CacheManager.h"
#import "Cache.h"

@implementation CacheManager


- (id) init {
	if (self = [super init]) {
		caches = [[NSMutableArray alloc] init];
		[caches addObject:[[Cache alloc] initWithCommand:@"getAllProcesses"]];
		[caches addObject:[[Cache alloc] initWithCommand:@"getAllScenarios"]];
		[caches addObject:[[Cache alloc] initWithCommand:@"getAllAssets"]];
		[caches addObject:[[Cache alloc] initWithCommand:@"getAllProcessAsset"]];
		[caches addObject:[[Cache alloc] initWithCommand:@"getAllAssetItInfrastructure"]];
		[caches addObject:[[Cache alloc] initWithCommand:@"getAllItInfrastructures"]];
	}
	return self;
}
- (void) fillInCaches {
	[NSThread detachNewThreadSelector:@selector(fillInCachesThreadMethod:) toTarget:self withObject:nil];
}
- (void) fillInCachesThreadMethod: (id) param {
	NSAutoreleasePool* pool = [[NSAutoreleasePool alloc] init];
	for (Cache* cache in caches) {
		[cache fillInCache];
	}
	[pool release];
}
- (void) dealloc {
	[super dealloc];
	[caches release];
}
@end
