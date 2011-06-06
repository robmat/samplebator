//
//  PathwayCell.m
//  smart_gp_ip
//
//  Created by User on 5/14/11.
//  Copyright 2011 __MyCompanyName__. All rights reserved.
//

#import "PathwayCell.h"
#import "WebViewController.h"

@implementation PathwayCell

@synthesize label, detailLabel, phoneBtn, urlBtn, navController, background;

- (id)initWithStyle:(UITableViewCellStyle)style reuseIdentifier:(NSString *)reuseIdentifier {
    if ((self = [super initWithStyle:style reuseIdentifier:reuseIdentifier])) {

    }
    return self;
}
- (void)setSelected:(BOOL)selected animated:(BOOL)animated {
    [super setSelected:selected animated:animated];
}
- (IBAction) urlAction: (id) sender {
	WebViewController* wvc = [[WebViewController alloc] initWithNibName:nil bundle:nil];
	NSString* urlStr = ((UIButton*) sender).titleLabel.text;
	if ([urlStr rangeOfString:@"http://"].location != 0) {
		urlStr = [NSString stringWithFormat:@"%@%@", @"http://", urlStr];
	}
	wvc.url = urlStr;
	[self.navController pushViewController:wvc animated:YES];
	[wvc release];
}
- (IBAction) phoneAction: (id) sender {
	NSString* telNo = [NSString stringWithFormat:@"%@%@", @"tel:", ((UIButton*) sender).titleLabel.text];
	NSURL *phoneNumberURL = [NSURL URLWithString:telNo];
	[[UIApplication sharedApplication] openURL:phoneNumberURL];
}
- (void)dealloc {
	[label release];
	[detailLabel release];
	[phoneBtn release];
	[urlBtn release];
	[background release];
	[navController release];
    [super dealloc];
}

@end
