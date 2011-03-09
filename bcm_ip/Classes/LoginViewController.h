//
//  LoginViewController.h
//  bcm_ip
//
//  Created by User on 3/5/11.
//  Copyright 2011 __MyCompanyName__. All rights reserved.
//

#import <UIKit/UIKit.h>


@interface LoginViewController : UIViewController <UITextFieldDelegate> {

	IBOutlet UIButton* loginBtn;
	IBOutlet UILabel* userLbl;
	IBOutlet UILabel* passLbl;
	IBOutlet UILabel* siteLbl;
	IBOutlet UITextField* userTxtFld;
	IBOutlet UITextField* passTxtFld;
	IBOutlet UITextField* siteTxtFld;
	
}
@property (nonatomic, retain) IBOutlet UIButton* loginBtn;
@property (nonatomic, retain) IBOutlet UILabel* userLbl;
@property (nonatomic, retain) IBOutlet UILabel* passLbl;
@property (nonatomic, retain) IBOutlet UILabel* siteLbl;
@property (nonatomic, retain) IBOutlet UITextField* userTxtFld;
@property (nonatomic, retain) IBOutlet UITextField* passTxtFld;
@property (nonatomic, retain) IBOutlet UITextField* siteTxtFld;


- (IBAction) loginAction: (id) sender;
- (void) animateTextField: (UITextField*) textField up: (BOOL) up;

@end
