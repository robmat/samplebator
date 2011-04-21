//
//  ELCTextfieldCell.m
//  MobileWorkforce
//
//  Created by Collin Ruffenach on 10/22/10.
//  Copyright 2010 ELC Tech. All rights reserved.
//

#import "ELCTextfieldCell.h"


@implementation ELCTextfieldCell

@synthesize delegate;
@synthesize leftLabel;
@synthesize rightTextField;
@synthesize indexPath;
@synthesize uiSwitch;
@synthesize tableView;

- (id)initWithStyle:(UITableViewCellStyle)style reuseIdentifier:(NSString *)reuseIdentifier switchable: (BOOL) switchable_ {
    
	if ((self = [super initWithStyle:style reuseIdentifier:reuseIdentifier])) {
		switchable = switchable_;
		leftLabel = [[UILabel alloc] initWithFrame:CGRectZero];
		[leftLabel setBackgroundColor:[UIColor clearColor]];
		[leftLabel setTextColor:[UIColor colorWithRed:.285 green:.376 blue:.541 alpha:1]];
		[leftLabel setFont:[UIFont fontWithName:@"Helvetica" size:12]];
		[leftLabel setTextAlignment:UITextAlignmentRight];
		[leftLabel setText:@"Left Field"];
		[self addSubview:leftLabel];
		
		if (switchable) {
			uiSwitch = [[UISwitch alloc] initWithFrame:CGRectZero];
			uiSwitch.contentVerticalAlignment = UIControlContentVerticalAlignmentCenter;
			[uiSwitch addTarget:self action:@selector(switchAction) forControlEvents: UIControlEventValueChanged];
			[self addSubview:uiSwitch];
			return self;
		}
		rightTextField = [[UITextField alloc] initWithFrame:CGRectZero];
		rightTextField.contentVerticalAlignment = UIControlContentVerticalAlignmentCenter;
		[rightTextField setDelegate:self];
		[rightTextField setPlaceholder:@"n/a"];
		[rightTextField setFont:[UIFont systemFontOfSize:17]];
		
		// FOR MWF USE DONE
		[rightTextField setReturnKeyType:UIReturnKeyDone];
		
		[self addSubview:rightTextField];
    }
	
    return self;
}
- (void) switchAction {
	if([delegate respondsToSelector:@selector(updateTextLabelAtIndexPath:string:)]) {		
		BOOL switched  = uiSwitch.on;
		[delegate performSelector:@selector(updateTextLabelAtIndexPath:string:) withObject:indexPath withObject:switched ? @"true" : @"false"];
	}
}
//Layout our fields in case of a layoutchange (fix for iPad doing strange things with margins if width is > 400)
- (void)layoutSubviews {
	[super layoutSubviews];
	CGRect origFrame = self.contentView.frame;
	if (leftLabel.text != nil) {
		leftLabel.frame = CGRectMake(origFrame.origin.x, origFrame.origin.y, 120, origFrame.size.height-1);
		if (switchable) {
			uiSwitch.frame = CGRectMake(origFrame.origin.x+125, origFrame.origin.y + 8, origFrame.size.width-120, origFrame.size.height-1);
			return;
		}
		rightTextField.frame = CGRectMake(origFrame.origin.x+125, origFrame.origin.y, origFrame.size.width-120, origFrame.size.height-1);	
	} else {
		leftLabel.hidden = YES;
		NSInteger imageWidth = 0;
		if (self.imageView.image != nil) {
			imageWidth = self.imageView.image.size.width + 5;
		}
		if (switchable) {
			uiSwitch.frame = CGRectMake(origFrame.origin.x+imageWidth+10, origFrame.origin.y + 8, origFrame.size.width-imageWidth-20, origFrame.size.height-1);
			return;
		}
		rightTextField.frame = CGRectMake(origFrame.origin.x+imageWidth+10, origFrame.origin.y, origFrame.size.width-imageWidth-20, origFrame.size.height-1);
	}
}
- (BOOL)textFieldShouldBeginEditing:(UITextField *)textView {
	[tableView scrollToRowAtIndexPath:indexPath atScrollPosition:UITableViewScrollPositionMiddle animated:YES];
	return YES;
}
- (void)setSelected:(BOOL)selected animated:(BOOL)animated {
	
    [super setSelected:selected animated:animated];
}
- (void)textFieldDidBeginEditing:(UITextField *)textField {
    [self animateView: textField up: YES];
}
- (void)textFieldDidEndEditing:(UITextField *)textField {
    [self animateView: textField up: NO];
}
- (void) animateView: (UITextField*) textField up: (BOOL) up {
    const int movementDistance = indexPath.section == 4 ? 180 : 0; // tweak as needed
    const float movementDuration = 1.0f; // tweak as needed
	
    int movement = (up ? -movementDistance : movementDistance);
	
    [UIView beginAnimations: @"anim" context: nil];
    [UIView setAnimationBeginsFromCurrentState: YES];
    [UIView setAnimationDuration: movementDuration];
    self.tableView.frame = CGRectOffset(self.tableView.frame, 0, movement);
    [UIView commitAnimations];
}
- (BOOL)textFieldShouldReturn:(UITextField *)textField {
	
	if([delegate respondsToSelector:@selector(textFieldDidReturnWithIndexPath:)]) {
		
		[delegate performSelector:@selector(textFieldDidReturnWithIndexPath:) withObject:indexPath];
	}
	
	return YES;
}

- (BOOL) textField:(UITextField *)textField shouldChangeCharactersInRange:(NSRange)range replacementString:(NSString *)string {
	
	NSString *textString = self.rightTextField.text;
	
	if (range.length > 0) {
		
		textString = [textString stringByReplacingCharactersInRange:range withString:@""];
	} 
	
	else {
		
		if(range.location == [textString length]) {
			
			textString = [textString stringByAppendingString:string];
		}
		
		else {
			
			textString = [textString stringByReplacingCharactersInRange:range withString:string];	
		}
	}
	
	if([delegate respondsToSelector:@selector(updateTextLabelAtIndexPath:string:)]) {		
		[delegate performSelector:@selector(updateTextLabelAtIndexPath:string:) withObject:indexPath withObject:textString];
	}
	
	return YES;
}

- (void)dealloc {
	[tableView release];
	[leftLabel release];
	[rightTextField release];
	[indexPath release];
	[uiSwitch release];
    [super dealloc];
}

@end
