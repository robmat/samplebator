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
	IBOutlet UITextView* detailLabel;
}

@property (nonatomic, retain) UILabel* label;
@property (nonatomic, retain) UITextView* detailLabel;

@end
