//
//  ELCTextfieldCell.h
//  MobileWorkforce
//
//  Created by Collin Ruffenach on 10/22/10.
//  Copyright 2010 ELC Tech. All rights reserved.
//

#import <UIKit/UIKit.h>


@interface ELCTextfieldCell : UITableViewCell <UITextFieldDelegate> {
	
	id delegate;
	UILabel *leftLabel;
	UITextField *rightTextField;
	NSIndexPath *indexPath;
	UISwitch* uiSwitch;
	BOOL switchable;
	UITableView* tableView;
}

@property (nonatomic, assign) id delegate;
@property (nonatomic, retain) UILabel *leftLabel;
@property (nonatomic, retain) UITextField *rightTextField;
@property (nonatomic, retain) NSIndexPath *indexPath;
@property (nonatomic, retain) UISwitch* uiSwitch;
@property (nonatomic, retain) UITableView* tableView;

-(void)animateView:(UITextField*)textField up:(BOOL)up;

@end

@protocol ELCTextFieldDelegate

-(id)initWithStyle:(UITableViewCellStyle)style reuseIdentifier:(NSString *)reuseIdentifier switchable: (BOOL) switchable_;
-(void)textFieldDidReturnWithIndexPath:(NSIndexPath*)_indexPath;
-(void)updateTextLabelAtIndexPath:(NSIndexPath*)_indexPath string:(NSString*)_string;

@end
