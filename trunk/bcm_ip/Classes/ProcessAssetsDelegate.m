//
//  ProcesDetailDelegate.m
//  bcm_ip
//
//  Created by User on 3/17/11.
//  Copyright 2011 __MyCompanyName__. All rights reserved.
//

#import "ProcessAssetsDelegate.h"
#import "ItemsViewController.h"
#import "AssetITInfrastructureDelegate.h"

@implementation ProcessAssetsDelegate

@synthesize navigationController;

- (void) detailClicked: (NSString*) idStr {
	ItemsViewController* procVC = [[ItemsViewController alloc] init];
	procVC.requestParams = [NSDictionary dictionaryWithObjectsAndKeys: @"getAssetsByProcess", @"action", idStr, @"processId", nil];
	procVC.xmlItemName = [NSString stringWithString:@"Asset"];
	AssetITInfrastructureDelegate* delegate = [[[AssetITInfrastructureDelegate alloc] init] autorelease];
	delegate.navigationController = self.navigationController;
	procVC.delegate = delegate;
	procVC.title = NSLocalizedString(@"assetsViewTitle", nil);
	procVC.dictionary = [[[[Dictionary alloc] init] loadDictionaryAndRetry:YES asynchronous:YES overwrite:NO] autorelease];
	procVC.dictionary.dictMappings = [NSDictionary dictionaryWithObjectsAndKeys: 
									  @"ASSET_STATUS_PROBE", @"StatusProbe", 
									  @"ASSET_STATUS", @"Status", 
									  @"ASSET_TYPE", @"Type", nil];	
	[self.navigationController pushViewController:procVC animated:YES];
	[procVC release];
	
}

- (void) dealloc {
	[navigationController release];
	[super dealloc];
}

@end
