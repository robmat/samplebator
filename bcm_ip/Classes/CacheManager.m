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
- (void) fillInCachesOverwrite: (BOOL) _overwrite {
	overwrite = _overwrite;
	[NSThread detachNewThreadSelector:@selector(fillInCachesThreadMethod:) toTarget:self withObject:nil];
}
- (void) fillInCachesThreadMethod: (id) param {
	NSAutoreleasePool* pool = [[NSAutoreleasePool alloc] init];
	for (Cache* cache in caches) {
		[cache fillInCacheOverwrite: overwrite];
	}
	[pool release];
}
- (Cache*) getCacheByCommand: (NSString*) command {
	for (Cache* cache in caches) {
		if ([cache.command isEqualToString:command]) {
			return cache;
		}
	}
	return nil;
}
- (NSArray*) getItInfraByAssetId: (NSString*) _assetId {
	Cache* cacheMap = [self getCacheByCommand:@"getAllAssetItInfrastructure"];
	NSMutableArray* itInfraIds = [NSMutableArray arrayWithCapacity:10];
	NSArray* cacheMapArray = [self convertXmlCacheToArrayOfDictionaries:cacheMap.xmlDoc];
	for (NSDictionary* dict in cacheMapArray) {
		NSString* assetId = [dict objectForKey:@"AssetId"];
		if ([assetId isEqualToString:_assetId]) {
			[itInfraIds addObject:[dict objectForKey:@"ItInfrastructureId"]];
		}
	}
	Cache* itInfraCache = [self getCacheByCommand:@"getAllItInfrastructures"];
	NSArray* itInfraCacheArray = [self convertXmlCacheToArrayOfDictionaries:itInfraCache.xmlDoc];
	NSMutableArray* results = [NSMutableArray arrayWithCapacity:10];
	for (NSString* itInfraId in itInfraIds) {
		for (NSDictionary* dict in itInfraCacheArray) {
			if ([[dict objectForKey:@"Id"] isEqualToString:itInfraId]) {
				[results addObject:dict];
			}
		}
	}
	return results;
}
- (NSArray*) getAssetsByProcessId: (NSString*) _processId {
	Cache* cacheMap = [self getCacheByCommand:@"getAllProcessAsset"];
	NSMutableArray* itInfraIds = [NSMutableArray arrayWithCapacity:10];
	NSArray* cacheMapArray = [self convertXmlCacheToArrayOfDictionaries:cacheMap.xmlDoc];
	for (NSDictionary* dict in cacheMapArray) {
		NSString* assetId = [dict objectForKey:@"BusinessProcessId"];
		if ([assetId isEqualToString:_processId]) {
			[itInfraIds addObject:[dict objectForKey:@"AssetId"]];
		}
	}
	Cache* itInfraCache = [self getCacheByCommand:@"getAllAssets"];
	NSArray* itInfraCacheArray = [self convertXmlCacheToArrayOfDictionaries:itInfraCache.xmlDoc];
	NSMutableArray* results = [NSMutableArray arrayWithCapacity:10];
	for (NSString* itInfraId in itInfraIds) {
		for (NSDictionary* dict in itInfraCacheArray) {
			if ([[dict objectForKey:@"Id"] isEqualToString:itInfraId]) {
				[results addObject:dict];
			}
		}
	}
	return results;
}
- (NSArray*) convertXmlCacheToArrayOfDictionaries: (TBXML*) tbXml {
	NSMutableArray* itemsArray = [NSMutableArray arrayWithCapacity:10];
	TBXMLElement* itemElem = tbXml->rootXMLElement->firstChild;
	
	while (itemElem) {
		TBXMLElement* itemChildElem = itemElem->firstChild;
		NSMutableDictionary* processDict = [NSMutableDictionary dictionaryWithCapacity:10];
		[itemsArray addObject:processDict];
			
		do {
			NSString* elemName = [TBXML elementName:itemChildElem];
			NSString* elemValu = [TBXML textForElement:itemChildElem];
			[processDict setObject:elemValu forKey:elemName];
		} while (itemChildElem = itemChildElem->nextSibling);
			
		itemElem = itemElem->nextSibling;
	}
	return itemsArray;
}
- (void) dealloc {
	[super dealloc];
	[caches release];
}
@end
