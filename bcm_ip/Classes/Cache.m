//
//  Cache.m
//  bcm_ip
//
//  Created by User on 4/15/11.
//  Copyright 2011 __MyCompanyName__. All rights reserved.
//

#import "Cache.h"
#import "HttpRequestWrapper.h"
#import "TBXML.h"

@implementation Cache

@synthesize command, xmlDoc;

- (id) initWithCommand: (NSString*) _command {
	self.command = _command;
	filled = NO;
	return self;
}
- (void) fillInCacheOverwrite: (BOOL) overwrite {
	if ([[NSFileManager defaultManager] fileExistsAtPath:[self getFilePath]] && !overwrite) {
		xmlDoc = [TBXML tbxmlWithXMLFile:[self getFilePath]];
	} else {
		HttpRequestWrapper* hrw = [[HttpRequestWrapper alloc] initWithDelegate:self];
		[hrw makeRequestWithParams:[NSDictionary dictionaryWithObjectsAndKeys: command, @"action", nil]];
	}
}
- (void)requestFinished:(ASIHTTPRequest *)request {
	xmlDoc = [[TBXML tbxmlWithXMLString:[request responseString]] retain];
	if (xmlDoc) { //check if really received xml
		NSString* path = [self getFilePath];
		[[request responseString] writeToFile:path atomically:YES encoding: NSUTF8StringEncoding  error: nil];
	}
	filled = YES;
}
- (NSString*) getFilePath {
	NSArray* paths = NSSearchPathForDirectoriesInDomains(NSDocumentDirectory, NSUserDomainMask, YES) ;
	return [[paths objectAtIndex:0] stringByAppendingPathComponent:[NSString stringWithFormat:@"%@%@", command, @".xml"]];
}
- (void)requestFailed:(ASIHTTPRequest *)request {
	filled = NO;
}
- (void) dealloc {
	[super dealloc];
	[command release];
	[xmlDoc release];
}
@end
