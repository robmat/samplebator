//
//  ProcesDetailDelegate.m
//  bcm_ip
//
//  Created by User on 3/17/11.
//  Copyright 2011 __MyCompanyName__. All rights reserved.
//

#import "ProcesDetailDelegate.h"
#import "ItemsViewController.h"


@implementation ProcesDetailDelegate

@synthesize navigationController;

- (void) detailClicked: (NSString*) idStr {
	ItemsViewController* procVC = [[ItemsViewController alloc] init];
	procVC.requestParams = [NSDictionary dictionaryWithObjectsAndKeys: @"getAssetsByProcess", @"action", idStr, @"processId", nil];
	procVC.xmlItemName = [NSString stringWithString:@"Asset"];
	procVC.delegate = [[[ProcesDetailDelegate alloc] init] autorelease];
	procVC.title = NSLocalizedString(@"assetsViewTitle", nil);
	[self.navigationController pushViewController:procVC animated:YES];
	[procVC release];
	
}
- (BOOL) respondsToSelector:(SEL)aSelector {
	return [super respondsToSelector:aSelector];
}
- (void) dealloc {
	[navigationController release];
	[super dealloc];
}

@end
