//
//  HttpRequestWrapper.m
//  bcm_ip
//
//  Created by User on 3/8/11.
//  Copyright 2011 __MyCompanyName__. All rights reserved.
//

#import "HttpRequestWrapper.h"
#import "ASIFormDataRequest.h"
#import "bcm_ipAppDelegate.h"
#import "Dictionary.h"

@implementation HttpRequestWrapper

- (HttpRequestWrapper*) initWithDelegate: (NSObject*) deleg {
	request = [[ASIFormDataRequest alloc] initWithURL: [NSURL URLWithString: [bcm_ipAppDelegate getFullURLWithSite]]];
	delegate = [deleg retain];
	return self;
}
- (void) makeRequestWithParams: (NSDictionary*) params {
	NSArray* loginData = [NSArray arrayWithContentsOfFile:[bcm_ipAppDelegate getLoginDataFilePath]];
	[request setPostValue: [loginData objectAtIndex:0] forKey:@"user"];
	[request setPostValue: [loginData objectAtIndex:1] forKey:@"password"];
	[request setPostValue: [UIDevice currentDevice].uniqueIdentifier forKey:@"devid"];
	[request setPostValue: [Dictionary localeAbbr] forKey:@"lang"];
	for (NSString* key in [params keyEnumerator]) {
		[request setPostValue: [params objectForKey:key] forKey:key];
	}
	[request setDelegate:delegate];
	[request startAsynchronous];
}
- (void) dealloc {
	[request release];
	[delegate release];
	[super dealloc];
}

@end
