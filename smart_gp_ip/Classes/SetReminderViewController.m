#import "SetReminderViewController.h"

@implementation SetReminderViewController

@synthesize textView, submitLabelText, datePicker, submitLabel, locNot, actionStr;

- (IBAction) setReminder {
	UILocalNotification* ln = [[UILocalNotification alloc] init];
	ln.fireDate = datePicker.date;
	ln.alertBody = [[submitLabelText stringByAppendingString:@" "] stringByAppendingString:textView.text];
	ln.alertAction = @"Ok";
	ln.soundName = UILocalNotificationDefaultSoundName;
	[[UIApplication sharedApplication] scheduleLocalNotification:ln];
	[ln release];
	if (locNot != nil) {
		[[UIApplication sharedApplication] cancelLocalNotification:locNot];
	}
	[self.navigationController popViewControllerAnimated:YES];
	UIAlertView* alert = [[UIAlertView alloc] initWithTitle:@"Reminder" 
													message:@"Your reminder has been saved." 
												   delegate:nil cancelButtonTitle:@"Ok" 
										  otherButtonTitles:nil];
	[alert show];
	[alert release];
}
- (void)viewDidLoad {
    [super viewDidLoad];
	self.submitLabel.text = submitLabelText;
	self.textView.text = actionStr;
	if ([submitLabel.text rangeOfString:@"CHECK"].location != NSNotFound) {
		[textView setKeyboardType:UIKeyboardTypeNumberPad];
	}
	self.datePicker.date = [NSDate date];
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
	[locNot release];
	[actionStr release];
	[[NSNotificationCenter defaultCenter] removeObserver:self];
    [super dealloc];
}
@end
