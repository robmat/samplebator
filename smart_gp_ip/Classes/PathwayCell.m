//
//  PathwayCell.m
//  smart_gp_ip
//
//  Created by User on 5/14/11.
//  Copyright 2011 __MyCompanyName__. All rights reserved.
//

#import "PathwayCell.h"


@implementation PathwayCell

@synthesize label, detailLabel, phoneBtn, urlBtn;

- (id)initWithStyle:(UITableViewCellStyle)style reuseIdentifier:(NSString *)reuseIdentifier {
    if ((self = [super initWithStyle:style reuseIdentifier:reuseIdentifier])) {

    }
    return self;
}
- (void)setSelected:(BOOL)selected animated:(BOOL)animated {
    [super setSelected:selected animated:animated];
}
- (void)dealloc {
	[label release];
	[detailLabel release];
	[phoneBtn release];
	[urlBtn release];
    [super dealloc];
}

@end
