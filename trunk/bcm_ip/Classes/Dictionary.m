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
#import "TBXML.h"
#import "bcm_ipAppDelegate.h"


@implementation Dictionary

+ (NSString*) localeAbbr{
	NSString* loc = [[NSLocale currentLocale] localeIdentifier];
	NSArray* locArr = [loc componentsSeparatedByString:@"_"];
	return [locArr objectAtIndex:0];
}
- (Dictionary*) loadDictionaryAndRetry: (BOOL) retry asynchronous: (BOOL) async overwrite: (BOOL) overwrite {
	NSString* filePath = [bcm_ipAppDelegate getDictFilePath];
	if ( [[NSFileManager defaultManager] fileExistsAtPath:filePath] && !overwrite) {
		NSString* xmlDocStr = [NSString stringWithContentsOfFile: filePath encoding:NSUTF8StringEncoding error:nil];
		xmlDoc = [[[TBXML alloc] initWithXMLString:xmlDocStr] retain];
		return self;
	}
	asynchronous = async;
	NSArray* loginData = [NSArray arrayWithContentsOfFile:[bcm_ipAppDelegate getLoginDataFilePath]];
	NSString* url = [bcm_ipAppDelegate getFullURLWithSite];
	NSURL* nsurl = [[[NSURL alloc] initWithString:url] autorelease];
	ASIFormDataRequest* request = [ASIFormDataRequest requestWithURL:nsurl];
	[request setPostValue: @"getAllDictionaries" forKey:@"action"];
	[request setPostValue: [loginData objectAtIndex:0] forKey:@"user"];
	[request setPostValue: [loginData objectAtIndex:1] forKey:@"password"];
	[request setPostValue: [UIDevice currentDevice].uniqueIdentifier forKey:@"devid"];
	[request setPostValue: [Dictionary localeAbbr] forKey:@"lang"];
	if (!async) {
		[request startSynchronous];
		NSString *responseString = [request responseString];
		xmlDoc = [[[TBXML alloc] initWithXMLString:responseString] retain];
		if (!xmlDoc && retry) {
			[self loadDictionaryAndRetry:NO asynchronous:async overwrite: overwrite];
		}
		[responseString writeToFile:[bcm_ipAppDelegate getDictFilePath] atomically:YES encoding: NSUTF8StringEncoding error: nil];
	} else {
		[request startAsynchronous];
	}
	return self;
}
- (NSString*) valueByDictionary: (NSString*) dictKey andKey: (NSString*) key {
	TBXMLElement* dictElem = [TBXML childElementNamed:@"Dictionary" parentElement: xmlDoc.rootXMLElement]; 
	do {
		TBXMLElement* typeElem = [TBXML childElementNamed:@"Type" parentElement:dictElem];
		TBXMLElement* keyElem = [TBXML childElementNamed:@"Key" parentElement:dictElem];
		if ( [[TBXML textForElement:typeElem] isEqual:dictKey] && [[TBXML textForElement:keyElem] isEqual:key] ) {
			NSString* valueElemName = [NSString stringWithFormat:@"%@%@", @"Value", [[Dictionary localeAbbr] uppercaseString] ];
			return [TBXML textForElement: [TBXML childElementNamed:valueElemName parentElement:dictElem ]];
		}
		dictElem = [TBXML nextSiblingNamed:@"Dictionary" searchFromElement:dictElem];
	} while (dictElem);
	return nil;
}
- (void)requestFinished:(ASIHTTPRequest *)request {
	NSString *responseString = [request responseString];
	xmlDoc = [[[TBXML alloc] initWithXMLString:responseString] retain];
	[responseString writeToFile:[bcm_ipAppDelegate getDictFilePath] atomically:YES encoding: NSUTF8StringEncoding error: nil];
}
- (void)requestFailed:(ASIHTTPRequest *)request {
	NSError *error = [request error];
	UIAlertView* alert = [[UIAlertView alloc] initWithTitle: NSLocalizedString(@"errorDialogTitle", nil) message: [error localizedDescription] delegate: nil cancelButtonTitle: @"OK" otherButtonTitles: nil];
	[alert show];
	[alert release];
}	

@end
