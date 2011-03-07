//
//  Dictionary.h
//  bcm_ip
//
//  Created by User on 3/6/11.
//  Copyright 2011 __MyCompanyName__. All rights reserved.
//

#import <Foundation/Foundation.h>
#import "TBXML.h"

@interface Dictionary : NSObject {
	TBXML* xmlDoc;
	BOOL asynchronous;
}

- (Dictionary*) loadDictionaryAndRetry: (BOOL) retry asynchronous: (BOOL) async;
- (NSString*) valueByDictionary: (NSString*) dictKey andKey: (NSString*) key;
+ (NSString*) localeAbbr;
@end
