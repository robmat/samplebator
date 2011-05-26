//
//  LogScreenViewController.h
//  smart_gp_ip
//
//  Created by User on 5/26/11.
//  Copyright 2011 __MyCompanyName__. All rights reserved.
//

#import <UIKit/UIKit.h>
#import <MessageUI/MessageUI.h>

@interface LogScreenViewController : UIViewController <UITextFieldDelegate, MFMailComposeViewControllerDelegate> {

	IBOutlet UITextField* date;
	IBOutlet UITextField* timeSpent;
	IBOutlet UITextField* activityType;
	IBOutlet UITextField* logTitle;
	IBOutlet UITextField* description;
	IBOutlet UITextField* lessonsLearnt;
	
}

@property (nonatomic, retain) UITextField* date;
@property (nonatomic, retain) UITextField* timeSpent;
@property (nonatomic, retain) UITextField* activityType;
@property (nonatomic, retain) UITextField* logTitle;
@property (nonatomic, retain) UITextField* description;
@property (nonatomic, retain) UITextField* lessonsLearnt;

- (void) animateTextField: (UITextField*) textField up: (BOOL) up;
- (IBAction) saveAction: (id) sender;
- (IBAction) sendAction: (id) sender;

@end
