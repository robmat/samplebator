//
//  AssetITInfrastructure.m
//  bcm_ip
//
//  Created by User on 3/17/11.
//  Copyright 2011 __MyCompanyName__. All rights reserved.
//

#import "AssetITInfrastructureDelegate.h"
#import "ItemsViewController.h"

@implementation AssetITInfrastructureDelegate

@synthesize navigationController;

- (void) detailClicked: (NSString*) idStr {
	ItemsViewController* procVC = [[ItemsViewController alloc] init];
	procVC.requestParams = [NSDictionary dictionaryWithObjectsAndKeys: @"getItInfrastructureByAsset", @"action", idStr, @"assetId", nil];
	procVC.xmlItemName = [NSString stringWithString:@"ItInfrastructure"];
	//procVC.delegate = [[[ProcesDetailDelegate alloc] init] autorelease];
	procVC.title = NSLocalizedString(@"assetsViewTitle", nil);
	procVC.dictionary = [[[[Dictionary alloc] init] loadDictionaryAndRetry:YES asynchronous:YES overwrite:NO] autorelease];
	procVC.dictionary.dictMappings = [NSDictionary dictionaryWithObjectsAndKeys: 
									  @"ITINFRASTRUCTURE_TYPE", @"Type", 
									  @"ITINFRASTRUCTURE_STATUS", @"Status", nil];	
	[self.navigationController pushViewController:procVC animated:YES];
	[procVC release];
	
}

- (void) dealloc {
	[navigationController release];
	[super dealloc];
}

@end
