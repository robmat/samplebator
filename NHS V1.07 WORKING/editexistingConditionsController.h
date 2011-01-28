//
//  editexistingConditionsController.h
//  PushViewControllerAnimated
//
//  Created by Andrew  Farmer on 13/04/2010.
//  Copyright 2010 Kioty Ltd. All rights reserved.
//

#import <UIKit/UIKit.h>


@interface editexistingConditionsController : UIViewController {

	IBOutlet UITextField * nameField ;
	IBOutlet UITextView * existingConditionsField ;
	
}


-(IBAction)saveAction:(id)sender ;


-(NSString *) dataFilePath ;

@end
