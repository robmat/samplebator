//
//  PathwayCell.h
//  smart_gp_ip
//
//  Created by User on 5/14/11.
//  Copyright 2011 __MyCompanyName__. All rights reserved.
//

#import <UIKit/UIKit.h>


@interface PathwayCell : UITableViewCell {
	IBOutlet UILabel* label;
	IBOutlet UILabel* detailLabel;
	IBOutlet UIButton* phoneBtn;
	IBOutlet UIButton* urlBtn;
}

@property (nonatomic, retain) UILabel* label;
@property (nonatomic, retain) UILabel* detailLabel;
@property (nonatomic, retain) UIButton* phoneBtn;
@property (nonatomic, retain) UIButton* urlBtn;

@end
