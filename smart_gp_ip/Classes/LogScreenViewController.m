//
//  LogScreenViewController.m
//  smart_gp_ip
//
//  Created by User on 5/26/11.
//  Copyright 2011 __MyCompanyName__. All rights reserved.
//

#import "LogScreenViewController.h"
#import <MessageUI/MessageUI.h>

@implementation LogScreenViewController

@synthesize date, timeSpent, activityType, logTitle, description, lessonsLearnt;

- (void)mailComposeController:(MFMailComposeViewController*)controller  
          didFinishWithResult:(MFMailComposeResult)result 
                        error:(NSError*)error {
	if (result == MFMailComposeResultSent) {
		NSLog(@"It's away!");
	} else {
		UIAlertView* alert = [[UIAlertView alloc] initWithTitle:@"Failure." 
														message:@"Sending the mail failed for unknown reason, try again later." 
													   delegate:nil 
											  cancelButtonTitle:@"Ok" 
											  otherButtonTitles:nil];
		[alert show];
		[alert release];
	}
	[self dismissModalViewControllerAnimated:YES];
	[self.navigationController popViewControllerAnimated:YES];
}
- (IBAction) sendAction: (id) sender {
	NSString* path = [self getFilePath];
	NSArray* arrayOfLogs = [NSArray arrayWithContentsOfFile:path];
	NSString* body = [NSString stringWithString:@""];
	body = [self prepareBody: body withItems: arrayOfLogs];
	MFMailComposeViewController* controller = [[MFMailComposeViewController alloc] init];
	controller.mailComposeDelegate = self;
	[controller setSubject:@"My Log"];
	[controller setMessageBody:body isHTML:NO]; 
	if (controller) [self presentModalViewController:controller animated:YES];
	[controller release];
}
- (NSString*) prepareBody: (NSString*) body withItems: (NSArray*) items {
	for (NSDictionary* dict in items) {
		for (NSString* key in [dict keyEnumerator]) {
			NSString* value = [dict objectForKey:key];
			body = [body stringByAppendingString:key];
			body = [body stringByAppendingString:@": "];
			body = [body stringByAppendingString:value];
			body = [body stringByAppendingString:@"\n"];
		}
		body = [body stringByAppendingString:@"\n"];
	}
	return body;
}
- (NSString*) getFilePath {
	NSArray* paths = NSSearchPathForDirectoriesInDomains(NSDocumentDirectory, NSUserDomainMask, YES) ;
	return [[paths objectAtIndex:0] stringByAppendingPathComponent:@"log_data.plist"];
}
- (IBAction) saveAction: (id) sender {
	NSString* path = [self getFilePath];
	BOOL fileExists = [[NSFileManager defaultManager] fileExistsAtPath:path];
	if (!fileExists) {
		[[NSArray array] writeToFile:path atomically:YES];
	}
	fileExists = [[NSFileManager defaultManager] fileExistsAtPath:path];
	if (fileExists) {
		NSMutableArray* arrayOfLogs = [NSMutableArray arrayWithContentsOfFile:path];
		NSDictionary* dict = [NSDictionary dictionaryWithObjectsAndKeys:date.text, @"Date",
																		timeSpent.text, @"Time spent",
																		activityType.text, @"Activity type",
																		logTitle.text, @"Title",
																		description.text, @"Descritpion",
																		lessonsLearnt.text, @"Lesson learnt", nil];
		[arrayOfLogs addObject:dict];
		[arrayOfLogs writeToFile:path atomically:YES];
		arrayOfLogs = [NSMutableArray arrayWithContentsOfFile:path];
		if (arrayOfLogs) {
			UIAlertView* alert = [[UIAlertView alloc] initWithTitle:@"Log created." message:@"Log creation succesful." delegate:nil cancelButtonTitle:@"Ok" otherButtonTitles:nil];
			[alert show];
			[alert release];
		}
	}
	[self.navigationController popViewControllerAnimated:YES];
}
- (void)viewDidLoad {
	date.delegate = self;
	timeSpent.delegate = self;
	activityType.delegate = self;
	logTitle.delegate = self;
	description.delegate = self;
	lessonsLearnt.delegate = self;
    [super viewDidLoad];
}
- (BOOL) textFieldShouldReturn:(UITextField *)textField {
	if (textField == date) {
		[timeSpent becomeFirstResponder];
	} else if (textField == timeSpent) {
		[activityType becomeFirstResponder];
	} else if (textField == activityType) {
		[logTitle becomeFirstResponder];
	} else if (textField == logTitle) {
		[description becomeFirstResponder];
	} else if (textField == description) {
		[lessonsLearnt becomeFirstResponder];
	} else if (textField == lessonsLearnt) {
		[lessonsLearnt resignFirstResponder];
	}
	return YES;
}
- (void)textFieldDidBeginEditing:(UITextField *)textField {
	if (textField == date || textField == timeSpent || textField == activityType) {
		return;
	}
    if (UI_USER_INTERFACE_IDIOM() != UIUserInterfaceIdiomPad) {
		[self animateTextField: textField up: YES];
	}	
}
- (void)textFieldDidEndEditing:(UITextField *)textField {
    if (textField == date || textField == timeSpent || textField == activityType) {
		return;
	}
	if (UI_USER_INTERFACE_IDIOM() != UIUserInterfaceIdiomPad) {
		[self animateTextField: textField up: NO];
	}
}

- (void) animateTextField: (UITextField*) textField up: (BOOL) up {
    const int movementDistance = 90; // tweak as needed
    const float movementDuration = 0.3f; // tweak as needed
    int movement = (up ? -movementDistance : movementDistance);
	
    [UIView beginAnimations: @"anim" context: nil];
    [UIView setAnimationBeginsFromCurrentState: YES];
    [UIView setAnimationDuration: movementDuration];
    self.view.frame = CGRectOffset(self.view.frame, 0, movement);
    [UIView commitAnimations];
}
- (void) viewWillAppear:(BOOL)animated {
    [self.navigationController setNavigationBarHidden:YES animated:animated];
    [super viewWillAppear:animated];
}
- (void) viewWillDisappear:(BOOL)animated {
    [self.navigationController setNavigationBarHidden:NO animated:animated];
    [super viewWillDisappear:animated];
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
