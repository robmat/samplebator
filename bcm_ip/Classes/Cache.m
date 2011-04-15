//
//  Cache.m
//  bcm_ip
//
//  Created by User on 4/15/11.
//  Copyright 2011 __MyCompanyName__. All rights reserved.
//

#import "Cache.h"
#import "HttpRequestWrapper.h"

@implementation Cache

@synthesize command;

- (id) initWithCommand: (NSString*) _command {
	self.command = _command;
	filled = NO;
	return self;
}
- (void) fillInCache {
	HttpRequestWrapper* hrw = [[HttpRequestWrapper alloc] initWithDelegate:self];
	[hrw makeRequestWithParams:[NSDictionary dictionaryWithObjectsAndKeys: command, @"action", nil]];
}
- (void)requestFinished:(ASIHTTPRequest *)request {
	NSLog([request responseString]);
	filled = YES;
}
- (void)requestFailed:(ASIHTTPRequest *)request {
	filled = NO;
}
- (void) dealloc {
	[super dealloc];
	[command release];
}
@end
