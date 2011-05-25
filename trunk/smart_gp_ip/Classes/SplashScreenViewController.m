//
//  SplashScreenViewController.m
//  smart_gp_ip
//
//  Created by User on 5/25/11.
//  Copyright 2011 __MyCompanyName__. All rights reserved.
//

#import "SplashScreenViewController.h"
#import "MainMenuViewController.h"

@implementation SplashScreenViewController

- (void) viewWillAppear:(BOOL)animated {
    [self.navigationController setNavigationBarHidden:YES animated:animated];
    [super viewWillAppear:animated];
}
- (void) viewWillDisappear:(BOOL)animated {
    [self.navigationController setNavigationBarHidden:NO animated:animated];
    [super viewWillDisappear:animated];
}
- (void)viewDidLoad {
    [super viewDidLoad];
	NSTimer* timer = [NSTimer timerWithTimeInterval:2 target:self selector:@selector(timeAction) userInfo:nil repeats:NO];
	[[NSRunLoop currentRunLoop] addTimer:timer forMode:NSDefaultRunLoopMode];
}
- (void) timeAction {
	MainMenuViewController* mmvc = [[MainMenuViewController alloc] initWithNibName:nil bundle:nil];
	[self.navigationController pushViewController:mmvc animated:YES];
	[mmvc release];
}
- (void)didReceiveMemoryWarning {
    [super didReceiveMemoryWarning];
}
- (void)viewDidUnload {
    [super viewDidUnload];
}
- (void)dealloc {
    [super dealloc];
}


@end
