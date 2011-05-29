//
//  SetReminderViewController.m
//  smart_gp_ip
//
//  Created by User on 5/29/11.
//  Copyright 2011 __MyCompanyName__. All rights reserved.
//

#import "SetReminderViewController.h"


@implementation SetReminderViewController

@synthesize textView, submitLabelText, datePicker, submitLabel;

- (void)viewDidLoad {
    [super viewDidLoad];
	submitLabel.text = submitLabelText;
}
- (void)didReceiveMemoryWarning {
    [super didReceiveMemoryWarning];
}
- (void)viewDidUnload {
    [super viewDidUnload];
}
- (BOOL)textView:(UITextView *)textVie shouldChangeTextInRange:(NSRange)range 
 replacementText:(NSString *)text {
	if ([text isEqualToString:@"\n"]) {
        [textVie resignFirstResponder];
        return FALSE;
    }
    return TRUE;
}
- (void)textViewDidBeginEditing:(UITextView *)textField {
    if (UI_USER_INTERFACE_IDIOM() != UIUserInterfaceIdiomPad) {
		[self animateTextField: textField up: YES];
	}	
}
- (void)textViewDidEndEditing:(UITextView *)textField {
	if (UI_USER_INTERFACE_IDIOM() != UIUserInterfaceIdiomPad) {
		[self animateTextField: textField up: NO];
	}
}

- (void) animateTextField: (UITextView*) textField up: (BOOL) up {
    const int movementDistance = 185; // tweak as needed
    const float movementDuration = 0.3f; // tweak as needed
    int movement = (up ? -movementDistance : movementDistance);
	
    [UIView beginAnimations: @"anim" context: nil];
    [UIView setAnimationBeginsFromCurrentState: YES];
    [UIView setAnimationDuration: movementDuration];
    self.view.frame = CGRectOffset(self.view.frame, 0, movement);
    [UIView commitAnimations];
}
- (void)dealloc {
	[textView release];
	[submitLabelText release];
	[datePicker release];
    [super dealloc];
}

@end
