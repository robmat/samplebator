//
//  Dictionary.m
//  bcm_ip
//
//  Created by User on 3/6/11.
//  Copyright 2011 __MyCompanyName__. All rights reserved.
//

#import "Dictionary.h"
#import "bcm_ipAppDelegate.h"
#import "ASIFormDataRequest.h"

static int dict;

@implementation Dictionary

+ (void) initDictionary {
	NSString* url = [bcm_ipAppDelegate getFullURLWithSite];
	NSURL* nsurl = [[[NSURL alloc] initWithString:url] autorelease];
	ASIFormDataRequest* request = [[ASIFormDataRequest alloc] initWithURL:nsurl];
	//TODO ended here
}

@end
